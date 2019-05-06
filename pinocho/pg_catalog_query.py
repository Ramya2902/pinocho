import psycopg2

conn = psycopg2.connect("host=192.1.242.15 dbname=hbcdm user=hbadmin password=hbadmin")
cur = conn.cursor()

#cur.execute("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")
#cur.execute("SELECT dataset_name FROM information_schema.columns WHERE table_name='data_catalog'")
cur.execute("SELECT dataset_name FROM data_catalog")
resulset = cur.fetchall()

for i in resulset:
    print(i)

