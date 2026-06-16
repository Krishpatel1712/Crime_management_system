# Run the Crime Management PHP app with Docker

Prerequisites: Docker and Docker Compose

Steps:

1. Open a terminal and change to the project directory:

```bash
cd "c:\Users\Krish\OneDrive\Desktop\6 Sem Project\crime menegment system  - PHP"
```

2. Start containers:

```bash
docker compose up -d
```

3. Visit the app in your browser:

http://localhost:8000

Database info (for the containerized MySQL instance):

- Host: `db` (from PHP code when running inside containers)
- From host machine: `localhost:3306`
- Database: `criminaldb`
- User: `appuser`
- Password: `apppass`
- Root password: `rootpass`

Notes:

- The `criminalinfo.sql` file in the project root is mounted into the MySQL container's init directory and will be imported automatically on first startup.
- If you already have MySQL running on host port 3306, stop it or change the port mapping in `docker-compose.yml`.
