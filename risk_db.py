import math
import mysql.connector
from mysql.connector import Error
try:
   mySQLconnection = mysql.connector.connect(host='http://72.36.65.71/humhub',
                             database='pinocho',
                             user='root',
                             password='root')

   sql_select_Query = "SELECT * FROM `request` WHERE id=(SELECT MAX(id) FROM `request`);;"
   cursor = mySQLconnection .cursor()
   cursor.execute(sql_select_Query)
   records = cursor.fetchall()

   for row in records:
       print("datatype = ", row[6], )
       print("UserId = ", row[1])

    if row[6] == 'identified':
        input_risk[1,95,3]
    elif row[6] == 'deidentified':
        input_risk[1,30,3]
    elif row[6] == 'limited':
        input_risk[1,45,3]
    elif row[6] == 'aggregated':
        input_risk[1,3,3]
    
       
   cursor.close()

# the user type for local and external
usertype = [1, 90]
#Data source can be local and external
datasource = [3, 95]
#Data type can range from Identified,Deidentified,Limited and Aggregated
datatype = [95,30,45,3]
input_risk = [1,3,3]
accept_risk = 25

# a risk function that calculates the probability of risk 

def risk_value(input_risk, accep_risk):
    total_risk = 0
    data_risk = math.log(accep_risk)
    for i in input_risk:
        total_risk = total_risk + math.log(i)
    risk_factor = math.exp(math.log(total_risk))

    if(risk_factor >= (1.25 * data_risk)):
        risk_level = "High"
    elif((risk_factor >= (0.75 * data_risk)) & (risk_factor <= (1.25 * data_risk))):
        risk_level = "Medium"
    else:
        risk_level = "Low"

    print('Risk factor: ', risk_factor)
    print('Data risk: ', data_risk)
    print('Risk level: ' , risk_level)
    return

# Result
risk_value(input_risk, accept_risk)