from flask import Flask, request, jsonify
from flask_cors import CORS
import face_recognition
import os
import ssl
import base64
from io import BytesIO
from PIL import Image

app = Flask(__name__)
CORS(app, resources={r"/compare": {"origins": "https://bettercallsergio.es:8080"}})

def compare_faces(user_image_path, temp_image_data):
    try:
        # Cargar la imagen del usuario desde la ruta proporcionada
        if not os.path.exists(user_image_path):
            return {'result': 'error', 'message': 'User image path does not exist.'}

        user_image = face_recognition.load_image_file(user_image_path)
        user_face_encoding = face_recognition.face_encodings(user_image)

        if len(user_face_encoding) == 0:
            return {'result': 'error', 'message': 'No face found in user image.'}

        # Decodificar la imagen temporal de la cámara
        temp_image = face_recognition.load_image_file(temp_image_data)
        temp_face_encoding = face_recognition.face_encodings(temp_image)

        if len(temp_face_encoding) == 0:
            return {'result': 'error', 'message': 'No face found in captured image.'}

        # Comparar las caras
        match = face_recognition.compare_faces([user_face_encoding[0]], temp_face_encoding[0])[0]
        if match:
            return {'result': 'success', 'match': str(match)}
        else:
            return {'result': 'error', 'match': str(match)}  # Convertir el booleano a cadena de texto
    except Exception as e:
        return {'result': 'error', 'message': str(e)}

@app.route('/compare', methods=['POST'])
def compare_faces_route():
    try:
        data = request.get_json()
        print("Received data:", data)

        user_image_path = data.get('user_image_path')
        temp_image_data = data.get('temp_image_data')

        if not user_image_path or not temp_image_data:
            return jsonify({'result': 'error', 'message': 'User image path and temp image data are required.'})

        #path_bueno = str(user_image_path).replace("/var/www/html", ".")

        # Decodificar la imagen temporal desde base64
        temp_image_data = temp_image_data.split(",")[1]
        temp_image_data = BytesIO(base64.b64decode(temp_image_data))
        temp_image = Image.open(temp_image_data)
        temp_image.save("./temp_image.jpg")  # Guardar la imagen temporal para su uso en comparación

        result = compare_faces(user_image_path, "./temp_image.jpg")
        return jsonify(result)
    except Exception as e:
        return jsonify({'result': 'error', 'message': str(e)})

if __name__ == '__main__':
    # Configura el contexto SSL
    ssl_context = ssl.create_default_context(ssl.Purpose.CLIENT_AUTH)
    ssl_context.load_cert_chain(certfile='/etc/ssl/certs/bettercallsergio.ddns.net.crt', keyfile='/etc/ssl/private/bettercallsergio.ddns.net.key')

    # Ejecuta la aplicación Flask con HTTPS
    app.run(host='0.0.0.0', port=5000, ssl_context=ssl_context)
