package com.fallphenix.webserviceapi.utils;

import java.lang.reflect.Type;
import java.text.SimpleDateFormat;

import com.google.gson.JsonElement;
import com.google.gson.JsonPrimitive;
import com.google.gson.JsonSerializationContext;
import com.google.gson.JsonSerializer;

public class DateSerializer implements JsonSerializer {

    @Override
    public JsonElement serialize(Object date, Type typeOfSrc, JsonSerializationContext context) {
	SimpleDateFormat sdf = new SimpleDateFormat("dd-MM-yyyy");
	return new JsonPrimitive(sdf.format(date));
    }


}
