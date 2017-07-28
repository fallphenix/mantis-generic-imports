package com.fallphenix.webserviceapi.security;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class SecurityController {
    @RequestMapping("/login")
    public String index() {

	return "login";
    }

    @RequestMapping("/")
    public String home() {

	return "redirect:/operations";
    }

    /*
     * @RequestMapping("/403") public String notPermit() {
     * 
     * return "403"; }
     */
}
