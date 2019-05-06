import requests
 
url = "https://72.36.65.71/humhub/index.php?r=dashboard%2Fdashboard/dataset"
request_id = str(uuid.uuid4()) 
headers = {
        "cache-control": "no-cache",
        'client-request-id': request_id,
        "return-client-request-id": "true"
        "Accept" : "Application/json"
        
}
 
response = requests.request("GET", url, headers=headers)
 
print(response.text)
