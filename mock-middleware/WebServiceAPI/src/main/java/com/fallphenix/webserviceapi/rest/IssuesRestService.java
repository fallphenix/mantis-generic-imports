package com.fallphenix.webserviceapi.rest;

import static java.lang.Math.min;

import java.io.FileWriter;
import java.io.IOException;
import java.io.Writer;
import java.lang.reflect.Type;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.stream.Collectors;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import com.fallphenix.webserviceapi.beans.Issue;
import com.fallphenix.webserviceapi.utils.CustomErrorType;
import com.fallphenix.webserviceapi.utils.ServiceIssue;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonParseException;
import com.google.gson.JsonPrimitive;
import com.google.gson.JsonSerializationContext;
import com.google.gson.JsonSerializer;



@RestController
@RequestMapping("/api")
public class IssuesRestService {
    public static final Logger logger = LoggerFactory.getLogger(IssuesRestService.class);

    

    @RequestMapping(value = "/issues", method = RequestMethod.GET)
    public ResponseEntity<HashMap<String, Object>> listAllIssues(
	    @RequestParam(name = "page", defaultValue = "0") int page,
	    @RequestParam(name = "size", defaultValue = "10") int size,
	    @RequestParam(name = "filter", defaultValue = "all") String filter) {

	List<Issue> issues = ServiceIssue.getInstance().getList();

	if (filter != null && filter.equals("created")) {
	    issues = issues.stream().filter(issue -> (issue.getAcknowledge() > 0)).collect(Collectors.toList());
	} else

	if (filter != null && filter.equals("new")) {
	    issues = issues.stream().filter(issue -> (issue.getAcknowledge() == 0)).collect(Collectors.toList());
	}

	HashMap<String, Object> response = new HashMap<>();

	response.put("size", issues.size());
	response.put("issues", issues.subList(page, min(page + size, issues.size())));

	return new ResponseEntity<HashMap<String, Object>>(response, HttpStatus.OK);
    }



    @RequestMapping(value = "/issues/{id}", method = RequestMethod.GET)
    public ResponseEntity<?> getUssue(@PathVariable("id") String id) {

	List<Issue> issues = ServiceIssue.getInstance().getList();
	Issue issue = null;
	Boolean trouve = false;
	int cpt = 0;
	while (!trouve && cpt < issues.size()) {
	    if (issues.get(cpt).getId().equals(id)) {
		issue = issues.get(cpt);
	    }
	    cpt++;
	}

	if (issue == null) {
	    CustomErrorType error = new CustomErrorType("Ussue with id " + id + " not found");
	    return new ResponseEntity(error, HttpStatus.NOT_FOUND);
	}
	return new ResponseEntity<Issue>(issue, HttpStatus.OK);
    }

    @RequestMapping(value = "/issues/acknowledge/{id}/{id_issue}", method = RequestMethod.GET)
    public Boolean acknowledge(@PathVariable("id") String id, @PathVariable("id_issue") int idIssue) {

	List<Issue> issues = ServiceIssue.getInstance().getList();
	Issue issue = null;
	Boolean trouve = false;
	int cpt = 0;
	while (!trouve && cpt < issues.size()) {
	    if (issues.get(cpt).getId().equals(id)) {
		issue = issues.get(cpt);
	    }
	    cpt++;
	}

	if (issue == null) {
	    return true;
	}

	issue.setAcknowledge(idIssue);

	JsonSerializer<Date> ser = new JsonSerializer<Date>() {
	    @Override
	    public JsonElement serialize(Date src, Type typeOfSrc, JsonSerializationContext context) {

		int de = 12;
		de++;
		if (de == 2) {
		}
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");

		return src == null ? null : new JsonPrimitive(sdf.format(src));
	    }
	};

	JsonDeserializer<Date> deser = new JsonDeserializer<Date>() {
	    @Override
	    public Date deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context)
		    throws JsonParseException {

		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
		Date date = null;
		try {
		    date = sdf.parse(json.getAsJsonPrimitive().getAsString());
		    return date;
		} catch (ParseException e) {
		    e.printStackTrace();
		}

		return json == null ? null : date;
	    }

	};

	try (Writer writer = new FileWriter("src/main/resources/static/data.json")) {
	    Gson gson = new GsonBuilder().registerTypeAdapter(Date.class, deser).registerTypeAdapter(Date.class, ser)
		    .create();
	    gson.toJson(issues, writer);
	} catch (IOException e) {
	    // TODO Auto-generated catch block
	    e.printStackTrace();
	    return false;
	}

	return true;
    }
}
