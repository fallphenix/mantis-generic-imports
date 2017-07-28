package com.fallphenix.webserviceapi;

import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.ImportResource;

@SpringBootApplication
@ImportResource("beans.xml")
public class WebServiceApiApplication implements CommandLineRunner {

	public static void main(String[] args) {
		SpringApplication.run(WebServiceApiApplication.class, args);
	}

    @Override
    public void run(String... arg0) throws Exception {

	
	// Gson gson = new Gson();
	// List<Issue> list = gson.fromJson(new
	// FileReader("src/main/resources/static/data.json"),
	// new TypeToken<List<Issue>>() {
	// }.getType());
	// list.forEach(x -> System.out.println(x));

    }
}
