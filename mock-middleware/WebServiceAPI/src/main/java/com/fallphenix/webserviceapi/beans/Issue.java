package com.fallphenix.webserviceapi.beans;

import java.text.SimpleDateFormat;
import java.util.Date;

import javax.xml.bind.annotation.adapters.XmlAdapter;
import javax.xml.bind.annotation.adapters.XmlJavaTypeAdapter;

import com.fasterxml.jackson.annotation.JsonFormat;
import com.google.gson.annotations.SerializedName;


public class Issue {

    @SerializedName("Id")
    private String id;

    @SerializedName("Project")
    private String projectId;

    @SerializedName("Reporter")
    private String reporterId;

    @SerializedName("AssignedTo")
    private String AssignedTo;

    @SerializedName("Priority")
    private String priority;

    @SerializedName("Severity")
    private String severity;

    @SerializedName("Reproducibility")
    private String reproducibility;

    @SerializedName("Category")
    private String Category;

    @SerializedName("Description")
    private String description;

    @SerializedName("Acknowledge")
    private int acknowledge;

    @SerializedName("Origine")
    private String origine;


    @SerializedName("DateSubmitted")
    // @XmlSchemaType(name = "dateSubmitted")
    @JsonFormat(shape = JsonFormat.Shape.STRING, pattern = "yyyy-MM-dd")
    private Date dateSubmitted;

    @SerializedName("Status")
    private String status;

    @SerializedName("Summary")
    private String Summary;

    @SerializedName("Resolution")
    private String resolution;

    public String getId() {
	return id;
    }

    public String getDescription() {
	return description;
    }

    public void setDescription(String description1) {
	description = description1;
    }

    public void setId(String id) {
	this.id = id;
    }





    public String getProjectId() {
	return projectId;
    }

    public void setProjectId(String projectId) {
	this.projectId = projectId;
    }

    public String getReporterId() {
	return reporterId;
    }

    public void setReporterId(String reporterId) {
	this.reporterId = reporterId;
    }



    public int getAcknowledge() {
	return acknowledge;
    }

    public void setAcknowledge(int acknowledge) {
	this.acknowledge = acknowledge;
    }

    public String getOrigine() {
	return origine;
    }

    public void setOrigine(String origine) {
	this.origine = origine;
    }

    public String getAssignedTo() {
	return AssignedTo;
    }

    public void setAssignedTo(String assignedTo) {
	AssignedTo = assignedTo;
    }

    public String getPriority() {
	return priority;
    }

    public void setPriority(String priority) {
	this.priority = priority;
    }

    public String getSeverity() {
	return severity;
    }

    public void setSeverity(String severity) {
	this.severity = severity;
    }

    public String getReproducibility() {
	return reproducibility;
    }

    public void setReproducibility(String reproducibility) {
	this.reproducibility = reproducibility;
    }

    public String getCategory() {
	return Category;
    }

    public void setCategory(String category) {
	Category = category;
    }

    @XmlJavaTypeAdapter(DateAdapter.class)
    public Date getDateSubmitted() {
	return dateSubmitted;
    }

    public void setDateSubmitted(Date dateSubmitted1) {
	dateSubmitted = dateSubmitted1;
    }

    public String getStatus() {
	return status;
    }

    public void setStatus(String status) {
	this.status = status;
    }

    public String getSummary() {
	return Summary;
    }

    public void setSummary(String summary) {
	Summary = summary;
    }

    public String getResolution() {
	return resolution;
    }

    public void setResolution(String resolution) {
	this.resolution = resolution;
    }

    @Override
    public String toString() {
	return "Issue [id=" + id + ", projectId=" + projectId + ", reporterId=" + reporterId + ", AssignedTo="
		+ AssignedTo + ", priority=" + priority + ", severity=" + severity + ", reproducibility="
		+ reproducibility + ", Category=" + Category + ", DateSubmitted=" + dateSubmitted + ", status=" + status
		+ ", Summary=" + Summary + ", resolution=" + resolution + "]";
    }

}

class DateAdapter extends XmlAdapter<String, Date> {

    private final SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");

    @Override
    public String marshal(Date v) throws Exception {
	synchronized (dateFormat) {
	    return dateFormat.format(v);
	}
    }

    @Override
    public Date unmarshal(String v) throws Exception {
	synchronized (dateFormat) {
	    return dateFormat.parse(v);
	}
    }

}