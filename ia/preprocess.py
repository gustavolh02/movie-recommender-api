import pandas as pd
from sklearn.model_selection import train_test_split

# Cargar los datos
ratings = pd.read_csv('data/ratings.csv')

# Dividir los datos en conjuntos de entrenamiento y prueba
train, test = train_test_split(ratings, test_size=0.2, random_state=42)

# Guardar los conjuntos de datos preprocesados
train.to_csv('data/train.csv', index=False)
test.to_csv('data/test.csv', index=False)
