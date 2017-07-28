package com.fallphenix.webserviceapi.soap;

import java.util.List;

import com.fallphenix.webserviceapi.beans.Issue;

public class HashMapWrapper {

    private int size;
    private List<Issue> issues;

    public HashMapWrapper(List<Issue> liste, int taille) {
	size = taille;
	issues = liste;
    }

    public int getSize() {
	return size;
    }

    public void setSize(int size) {
	this.size = size;
    }

    public List<Issue> getIssues() {
	return issues;
    }

    public void setIssues(List<Issue> issues) {
	this.issues = issues;
    }

}
