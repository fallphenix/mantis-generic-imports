package com.fallphenix.webserviceapi.soap;

import static java.lang.Math.min;

import java.io.FileWriter;
import java.io.IOException;
import java.io.Writer;
import java.lang.reflect.Type;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.stream.Collectors;

import javax.jws.WebMethod;
import javax.jws.WebParam;
import javax.jws.WebService;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Component;

import com.fallphenix.webserviceapi.beans.Issue;
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
@WebService
@Component
public class IssuesSOAPServices {

    final static Logger logger = LoggerFactory.getLogger(IssuesSOAPServices.class);




    @WebMethod(operationName = "listAllIssues")
    public HashMapWrapper listAllIssues(@WebParam(name = "page") int page,
	    @WebParam(name = "size") int size, @WebParam(name = "filter") String filter) {

	List<Issue> issues = ServiceIssue.getInstance().getList();
	
	if (filter != null && filter.equals("created")) {
	    issues = issues.stream().filter(issue -> (issue.getAcknowledge() > 0)).collect(Collectors.toList());
	} else

	if (filter != null && filter.equals("new")) {
	    issues = issues.stream().filter(issue -> (issue.getAcknowledge() == 0)).collect(Collectors.toList());
	}

	HashMapWrapper response = new HashMapWrapper(issues.subList(page, min(page + size, issues.size())),
		issues.size());
	

	return response;
    }

    @WebMethod(operationName = "getIssue")
    public Issue getUssue(@WebParam(name = "id") String id) {

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

	return issue;
    }

    @WebMethod(operationName = "acknowledge")
    public Boolean acknowledge(@WebParam(name = "id") String id, @WebParam(name = "id_issue") int idissue) {

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

	issue.setAcknowledge(idissue);

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
