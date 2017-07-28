package com.fallphenix.webserviceapi.security;

import javax.sql.DataSource;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Configurable;
import org.springframework.http.HttpMethod;
import org.springframework.security.config.annotation.authentication.builders.AuthenticationManagerBuilder;
import org.springframework.security.config.annotation.method.configuration.EnableGlobalMethodSecurity;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.builders.WebSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.config.annotation.web.configuration.WebSecurityConfigurerAdapter;

@Configurable
@EnableWebSecurity
@EnableGlobalMethodSecurity(securedEnabled = true)
public class SecurityConfig extends WebSecurityConfigurerAdapter {

	@Autowired
	private DataSource dataSource;

	@Override
	protected void configure(AuthenticationManagerBuilder auth) throws Exception {


	auth.inMemoryAuthentication().withUser("admin").password("admin").roles("ADMIN", "USER");
	auth.inMemoryAuthentication().withUser("user").password("1234").roles("USER");

	}

    @Override
	protected void configure(HttpSecurity http) throws Exception {


	http.authorizeRequests().antMatchers("/api/**").permitAll();
				

	}

	@Override
	public void configure(WebSecurity web) throws Exception {
		web.ignoring().antMatchers(HttpMethod.POST, "/api/**");
		web.ignoring().antMatchers(HttpMethod.PUT, "/api/**");
		web.ignoring().antMatchers(HttpMethod.DELETE, "/api/**");

	}
}
