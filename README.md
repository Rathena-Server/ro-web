# FluxCP for Adornado Ragnarok Server

FluxCP control panel running in Docker for rAthena server management.

## Configuration

- **Server Name**: Adornado
- **Access URL**: http://194.233.71.190:8080
- **Admin Account**: admin01

## Docker Setup

```bash
docker-compose up -d --build
```

## Database Configuration

- **Main Database**: main2025 (character, login, logs)
- **Web Database**: web2025 (FluxCP tables)
- **Database Views**: item_db*, mob_db* (dynamic links to ragnarok database)

## Server Connections

- Login Server: rathena-login-ubuntu:6900
- Char Server: rathena-char-ubuntu:6121
- Map Server: rathena-map-ubuntu:5121

## Features

✅ Real-time server status monitoring
✅ Player online count tracking
✅ Account management
✅ Character viewing
✅ Item/Mob database browsing
✅ Dynamic database access (no data copying)
