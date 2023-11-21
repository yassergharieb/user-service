- installation
  - copy .env.example to .env
  - run
  - docker-compose build && docker-compose up -d
  - queue
    - when user register, create a welcome note for him 
    - when user add note, send to the user-service that the user created note
    
  