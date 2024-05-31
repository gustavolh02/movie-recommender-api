import tensorflow as tf
import pandas as pd
import numpy as np

# Cargar los datos
train = pd.read_csv('data/train.csv')
test = pd.read_csv('data/test.csv')

# Obtener el número de usuarios y películas únicas
num_users = len(train['user_id'].unique())
num_movies = len(train['movie_id'].unique())

# Crear un modelo de recomendación
class RecommenderModel(tf.keras.Model):
    def __init__(self, num_users, num_movies, embedding_dim=50):
        super(RecommenderModel, self).__init__()
        self.user_embedding = tf.keras.layers.Embedding(num_users, embedding_dim)
        self.movie_embedding = tf.keras.layers.Embedding(num_movies, embedding_dim)
        self.dot = tf.keras.layers.Dot(axes=1)

    def call(self, inputs):
        user_vector = self.user_embedding(inputs[:, 0])
        movie_vector = self.movie_embedding(inputs[:, 1])
        dot_product = self.dot([user_vector, movie_vector])
        return dot_product

# Instanciar el modelo
model = RecommenderModel(num_users, num_movies)

# Compilar el modelo
model.compile(optimizer='adam', loss='mean_squared_error')

# Preparar los datos para el entrenamiento
train_input = train[['user_id', 'movie_id']].values
train_target = train['rating'].values

# Entrenar el modelo
model.fit(train_input, train_target, epochs=10, batch_size=64)

# Guardar el modelo
model.save('models/movie_recommender_model')
