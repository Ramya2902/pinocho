#!/usr/bin/env python
import math
import mysql.connector
from mysql.connector import Error

input_risk = [1,3,3]
request_id = 0

try:
    mySQLconnection = mysql.connector.connect(host='localhost',
                             database='pinocho',
                             user='root',
                             password='root')

    sql_select_Query = "SELECT * FROM `request` WHERE id=(SELECT MAX(id) FROM `request`);;"
    #sql_select_Query = "SELECT * FROM `request` WHERE id=3;"
    cursor = mySQLconnection.cursor()
    cursor.execute(sql_select_Query)
    records = cursor.fetchall()

    for row in records:
        print("datatype = " + row[6])
        print("UserId = " + str(row[1]))
        request_id = row[0]

    if row[6] == 'identified':
        input_risk[1] = 95
        input_risk[2] = 3
    elif row[6] == 'deidentified':
        input_risk[1] = 30
        input_risk[2] = 3
    elif row[6] == 'limited':
        input_risk[1] = 45
        input_risk[2] = 3
    elif row[6] == 'aggregated':
        input_risk[1] = 3
        input_risk[2] = 3
    
       
    cursor.close()
except:
    print("exception occured")

# the user type for local and external
#usertype = [1, 90]
#Data source can be local and external
#datasource = [3, 95]
#Data type can range from Identified,Deidentified,Limited and Aggregated
#datatype = [95,30,45,3]
#input_risk = [1,3,3]

accept_risk = 25

# a risk function that calculates the probability of risk 

def risk_value(input_risk, accept_risk, request_id):
    total_risk = 0
    data_risk = math.log(accept_risk)
    for i in input_risk:
        total_risk = total_risk + math.log(i)
    risk_factor = math.exp(math.log(total_risk))

    if(risk_factor >= (1.25 * data_risk)):
        risk_level = "high"
    elif((risk_factor >= (0.75 * data_risk)) & (risk_factor <= (1.25 * data_risk))):
        risk_level = "medium"
    else:
        risk_level = "low"

    print('Risk factor: ', risk_factor)
    print('Data risk: ', data_risk)
    print('Risk level: ' , risk_level)

    try:
        mySQLconnection = mysql.connector.connect(host='localhost',
                             database='pinocho',
                             user='root',
                             password='root')

        sql_update_Query = "UPDATE `request` SET risk = %s WHERE id = %s;"
        print(sql_update_Query)
        values = (risk_level, request_id)
        #sql_update_Query = "SELECT * FROM `request` WHERE id=3;"
        cursor = mySQLconnection.cursor()
        cursor.execute(sql_update_Query, values)
        mySQLconnection.commit()
    except mysql.connector.Error as err:
        print("An exception updating the request occurred: {}".format(err))


    return

# Result
risk_value(input_risk, accept_risk, request_id)
