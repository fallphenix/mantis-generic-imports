package com.fallphenix.webserviceapi.utils;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.lang.reflect.Type;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

import com.fallphenix.webserviceapi.beans.Issue;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonIOException;
import com.google.gson.JsonParseException;
import com.google.gson.JsonPrimitive;
import com.google.gson.JsonSerializationContext;
import com.google.gson.JsonSerializer;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;

public class ServiceIssue {

    private static ServiceIssue _instance;

    private ServiceIssue() {

    }

    public static ServiceIssue getInstance() {
	if (_instance == null) {
	    _instance = new ServiceIssue();
	}

	return _instance;
    }

    public List<Issue> getList() {

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

	Gson gson = new GsonBuilder().registerTypeAdapter(Date.class, deser).registerTypeAdapter(Date.class, ser)
		.create();
	// Gson gson = new Gson();
	List<Issue> issues = null;
	try {

	    issues = gson.fromJson(new FileReader("src/main/resources/static/data.json"), new TypeToken<List<Issue>>() {
	    }.getType());
	} catch (JsonIOException e) {
	    // TODO Auto-generated catch block
	    e.printStackTrace();
	} catch (JsonSyntaxException e) {
	    // TODO Auto-generated catch block
	    e.printStackTrace();
	} catch (FileNotFoundException e) {
	    // TODO Auto-generated catch block
	    e.printStackTrace();
	}
	return issues;
    }
}
