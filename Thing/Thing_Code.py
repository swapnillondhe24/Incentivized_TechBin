import face_recognition
import numpy as np
import cv2, queue, threading, time
import requests, os, re
import time
import sys
import RPi.GPIO as GPIO
from hx711 import HX711
import mysql.connector
import schedule
#----------- Get sensor Weight and Tareing is done---------------

referenceUnit = 488
hx = HX711(5,6)
hx.set_reading_format("MSB", "MSB")
hx.set_reference_unit(referenceUnit)
hx.reset()
hx.tare()
INITIAL_WEIGHT = hx.get_weight(5)
# print("initial weight : " + str(INITIAL_WEIGHT))
def cleanAndExit():
    GPIO.cleanup()
    
def getSensorWeight():
    try:
        time.sleep(5)
        val = hx.get_weight(5)
        print(val)
        
        return val

        hx.power_down()
        hx.power_up()
        time.sleep(0.1)

    except(KeyboardInterrupt, SystemExit):
        cleanAndExit()
    finally:
        cleanAndExit()
        
#------------------- Data Base Stuff -------------------



mydb = mysql.connector.connect(
  # host="",  Your Database Hostname
  # user="", Your Database server hostUsername
  # password="", Database server Host Password
  # database="" Your database name
)

mycursor = mydb.cursor(buffered=True)

def createTable():
    create_table = "CREATE TABLE IF NOT EXISTS records (empid INT PRIMARY KEY, name VARCHAR(15),password VARCHAR(32),image MEDIUMBLOB,weight INT DEFAULT 0)"
    mycursor.execute(create_table)
createTable()

def getWeight(empId,SensorWeight=0):
    sql_get_weight = """SELECT weight from records where empid = %s"""
    mycursor.execute(sql_get_weight,(empId,))
    record = mycursor.fetchall()
    weight = record[0][0]
    if not weight:
        return SensorWeight
        
    else:
        return weight+SensorWeight
    
def updatedb(empID,weight):
    sql = "UPDATE records SET weight = %s WHERE empid = %s"
    val = (getWeight(empID,weight),empID)
    mycursor.execute(sql, val)
    mydb.commit()
    print(mycursor.rowcount, "record Insert Success.")
    
def write_file(data, filename):
    # Convert binary data to proper format and write it on Hard Disk
    with open(filename, 'wb') as file:
        file.write(data)

def resetmonthly():
    sql = "UPDATE records SET weight = %s"
    val = (0,)
    mycursor.execute(sql, val)
    mydb.commit()
    print(mycursor.rowcount, "record Insert Success.")


def readBLOB():
    print("Reading BLOB data from records table")

    try:
        mycursor = mydb.cursor()
        sql_fetch_blob_query = """SELECT empid,image from records """

        mycursor.execute(sql_fetch_blob_query)
        record = mycursor.fetchall()
        for row in record:
            id = row[0]
            print("Id = ", id, )
            image = row[1]
            print("Storing employee image on disk \n")
            write_file(image, "assets/img/users/"+str(id)+".jpg")

    except mysql.connector.Error as error:
        print("Failed to read BLOB data from MySQL table {}".format(error))
        continue

    finally:
        pass

# ------------------------ Scheduler ------------------
def job():
    getUsers()
def monthlyjob():
    resetmonthly()
        
# ---------------------- capture Video ------------------
# bufferless VideoCapture
class VideoCapture:
    def __init__(self, name):
        self.cap = cv2.VideoCapture(name)
        self.q = queue.Queue()
        t = threading.Thread(target=self._reader)
        t.daemon = True
        t.start()

    # read frames as soon as they are available, keeping only most recent one
    def _reader(self):
        while True:
            ret, frame = self.cap.read()
            if not ret:
                break
            if not self.q.empty():
                try:
                    self.q.get_nowait()   # discard previous (unprocessed) frame
                except queue.Empty:
                    pass
            self.q.put(frame)

    def read(self):
        return self.q.get()

# Select the webcam of the computer
video_capture = VideoCapture(0)





# * -------------------- USERS -------------------- *

known_face_encodings = []
known_face_names = []
known_faces_filenames = []

def getUsers():
    readBLOB()

    for (dirpath, dirnames, filenames) in os.walk('assets/img/users/'):
        known_faces_filenames.extend(filenames)
        break
        
    for filename in known_faces_filenames:
        face = face_recognition.load_image_file('assets/img/users/' + filename)
        known_face_names.append(str(filename[:-4]))
        known_face_encodings.append(face_recognition.face_encodings(face)[0])


getUsers()
schedule.every(5).to(10).minutes.do(job)
schedule.every(4).weeks.do(monthlyjob)

face_locations = []
face_encodings = []
face_names = []
process_this_frame = True


# --------------- Staring Recognition ------------------------

while True:
    schedule.run_pending()

    frame = video_capture.read()

    if process_this_frame:
        # Find all the faces and face encodings in the current frame of video
        face_locations = face_recognition.face_locations(frame)
        face_encodings = face_recognition.face_encodings(frame, face_locations)
        
        # Initialize an array for the name of the detected users
        face_names = []
        
        for face_encoding in face_encodings:
            # See if the face is a match for the known face(s)
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            name = "Unknown"


            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            best_match_index = np.argmin(face_distances)
            if matches[best_match_index]:
                name = known_face_names[best_match_index]
                
                print(name)
                
# ------------------ Writing Latest detected Person to txt ---------------------------
                f_write = open("demofile.txt","w")
                f_write.writelines(name)
                f_write.close()

# ------------------ Writing Latest detected Person and weight to data base ---------------------------
                w = INITIAL_WEIGHT - getSensorWeight()
                f_read = open("demofile.txt","r") #read latest empolyee from file and write it to db
                updatedb(f_read.readline(),w)


            face_names.append(name)
        
    process_this_frame = not process_this_frame

    # Hit 'q' on the keyboard to quit!
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

        
        
        
# ------------ Release handle to the webcam and clean Rest of stuff

cleanAndExit()
video_capture.release()
cv2.destroyAllWindows()