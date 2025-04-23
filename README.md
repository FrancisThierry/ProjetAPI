# Lancer les containers avec Docker Compose

docker-compose up --build -d
## Accéder à l'API
L'API sera accessible à cette adresse :


http://localhost:8080
🔧 Endpoints de l’API et commandes curl
Toutes les requêtes sont envoyées sur / (racine), avec différents verbes HTTP.

### Ajouter une mesure (POST)

curl -X POST http://localhost:8080 \
  -H "Content-Type: application/json" \
  -d '{"location": "Frigo1", "temperature": 4.5}'
### Récupérer toutes les mesures (GET)

curl http://localhost:8080
### Récupérer une mesure par ID (GET)

curl http://localhost:8080?id=1
### Mettre à jour une mesure (PUT)

curl -X PUT http://localhost:8080 \
  -H "Content-Type: application/json" \
  -d '{"id": 1, "location": "Frigo2", "temperature": 5.2}'
### Supprimer une mesure (DELETE)

curl -X DELETE http://localhost:8080 \
  -H "Content-Type: application/json" \
  -d '{"id": 1}'
