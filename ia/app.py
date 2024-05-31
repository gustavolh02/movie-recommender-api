from flask import Flask, request, jsonify
import tensorflow as tf
import numpy as np
import pandas as pd

# Cargar el modelo
model = tf.keras.models.load_model('models/movie_recommender_model')

# Cargar los datos de las películas (por ejemplo, ids y títulos)
movies = pd.read_csv('data/movies.csv')  # Asegúrate de tener un archivo con los detalles de las películas

app = Flask(__name__)

@app.route('/recommend', methods=['POST'])
def recommend():
    data = request.get_json()
    user_id = data['user_id']
    # Generar predicciones para todas las películas
    movie_ids = movies['movie_id'].values
    user_ids = np.array([user_id] * len(movie_ids))
    inputs = np.vstack([user_ids, movie_ids]).T
    predictions = model.predict(inputs).flatten()
    top_indices = predictions.argsort()[-10:][::-1]
    recommended_movies = movies.iloc[top_indices]
    return jsonify(recommended_movies.to_dict(orient='records'))

if __name__ == '__main__':
    app.run(debug=True)
