dus bijv alle oefeningen ophalen kan zo (GET)
https://summa-move-api.vercel.app/api/exercises/cmke02xmv0006phsjc0yy6ofk

bijv een specifieke oefening ophalen kan zo: (GET)
https://summa-move-api.vercel.app/api/exercises/cmke02xmv0006phsjc0yy6ofk

Oefening aanmaken kan zo: (POST)
https://summa-move-api.vercel.app/api/exercises/ en dan dus data in de body
bijv dit:

````json
{
  "name": "Push-ups",
  "description": "Klassieke push-up oefening",
  "instructions": "Doe 3 sets van 10 herhalingen",
  "category": "Kracht",
  "difficulty": "Beginner"
}```

oefening updaten kan zo: (PUT)
https://summa-move-api.vercel.app/api/exercises/cmke02xmv0006phsjc0yy6ofk

en dan in de body de dingen die je wil updaten dus bijv
```json
{
  "name": "Push-ups V2 en Push-ups V1"
}```

oefening verwijderen kan zo: (DELETE)
https://summa-move-api.vercel.app/api/exercises/cmke02xmv0006phsjc0yy6ofk
````
