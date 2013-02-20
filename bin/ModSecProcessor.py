#! /opt/python/bin/python

import glob
from threading import *
import optparse
import os
import calendar
import time
import re
import MySQLdb as mdb

dbhost = 'localhost'
dbname = 'mod_sec'
dbuser = 'modsec'
dbpass = 'xxxxxx'

screenLock = Semaphore(value=1)

def InsertDB(query):
    # Con esta funcion conectamos a la db e insertamos
    global db
    
    try:
        screenLock.acquire()
        db = mdb.connect(dbhost,dbuser,dbpass,dbname)
        c = db.cursor()
        c.execute(query)
        db.commit()
        db.close()
        screenLock.release()
        return True
    except mdb.Error, e:
        print "\t\t\t[-] Database Error: " + str(e)
        print query
        screenLock.release()
        return False
    except:
        print "\t\t\t[-] Unknown Error"
        print query
        screenLock.release()
        return False



# Le pasamos como parametro el numero del hilo y fichero a procesar
def ProcessFiles(hilo, fichero):
    
    global get
    global host
    global date
    global referer
    global cookie
    global xff
    global ua
    global ae
    global message
    global l
    global file_content
    
    
    file_name = os.path.basename(fichero)
    attack_year = int(file_name[0:4])
    attack_month = int(file_name[4:6])
    attack_day = int(file_name[6:8])
    attack_hour = int(file_name[9:11])
    attack_min = int(file_name[11:13])
    attack_sec = int(file_name[13:15])
    
    
    t = (attack_year, attack_month, attack_day, attack_hour, attack_min, attack_sec)
    ts= calendar.timegm(t)
    # Le restamos una hora para que sea compatible con lo que busca la aplicacion web
    ts = ts - 3600
    
    
    # Abrimos el fichero para su parseo
    try:
        print "\t\t[+] " + str(hilo) + ":" + fichero
        f = open(fichero, 'r')
        file_content = str(f.readlines(1000000))
        f.close()
    except:
        print "\t\t\t[-] " + fichero + ": No Existe!"
        return False
        
    
    l = re.sub('\'','', file_content)
    lines = []
    lines = l.split('\\n')
    get = ""
    host = ""
    date = ""
    cookie = ""
    referer = ""
    ua = ""
    ae = ""
    xff = ""
    message = ""
    
    for line in lines:
        line = re.sub('^,','', line)
        
        
        
        m = re.search('([GET|POST].*)HTTP', line)
        if m:
            get = m.group(1)

        m = re.search('(Date.*)', line)
        if m:
            date = m.group(1)

        m = re.search('(Host.*)', line)
        if m:
            host = m.group(1)

        m = re.search('(Cookie.*)', line)
        if m:
            cookie = m.group(1)

        m = re.search('(Referer.*)', line)
        if m:
            referer = m.group(1)

        m = re.search('(User-Agent.*)', line)
        if m:
            ua = m.group(1)

        m = re.search('(Accept-Encoding.*)', line)
        if m:
            ae = m.group(1)

        m = re.search('(X-Forwarded-For.*)', line)
        if m:
            xff = m.group(1)

        m = re.search('(Message.*)', line)
        if m:
            message = m.group(1)
       
    
     
    
    query = "INSERT INTO log (file_id, host, get, date, cookie, referer, ua, xff, ae, message, timestamp) VALUES (\'" + mdb.escape_string(file_name) + "\', \'" + mdb.escape_string(host) + "\', \'" + mdb.escape_string(get) + "\', \'" + mdb.escape_string(date) + "\', \'" + mdb.escape_string(cookie) + "\', \'" + mdb.escape_string(referer) + "\', \'" + mdb.escape_string(ua) + "\', \'" + mdb.escape_string(xff) + "\', \'" + mdb.escape_string(ae) + "\', \'" + mdb.escape_string(message) + "\', \'" + str(ts) + "\');"

    InsertDB(query)
    #print "\t\t\t[-] Borrando: " + fichero
    os.remove(fichero)
    
def main():
    
    basedir = "/LOGS/mod_sec"
    logsdir = basedir + "/logs"
    global files
    
    parser = optparse.OptionParser('./ModSecProcessor.py ' + '-t <threads>')
    parser.add_option('-t', dest='concurrent', type='int',help='Especifica el numero de threads a utilizar para lanzar el proceso')
        
    (options, args) = parser.parse_args()
        
    concurrent = options.concurrent
            
    if (concurrent == None):
        print parser.usage
        exit(0)
    
    print " [*] Obtiendo listado de ficheros a procesar:"
    # Formato del fichero a buscar: 20130213-104032-URtfkKwSAxYAAHYN9-IAAAA 
    
    files = glob.glob(logsdir + '*/*/*/*');
    toprocess = len(files)
    print "\t [+] Ficheros a procesar " + str(toprocess)
        
        
    count = 0    
    # Lanzamos los threads
    for i in range(toprocess):
        count += 1
        t = Thread(target=ProcessFiles, args=(i,files[i]))
        t.start()
        if count == concurrent:
            t.join()
            count = 0
        
    
if __name__ == "__main__":
    main()
