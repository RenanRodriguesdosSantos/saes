## SISTEMA DE ATENDIMENTO PARA ESTABELECIMENTOS DE SAÚDE

### Requisitos

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Instalação

```bash
$ git clone <remote> [<folder>] && cd <folder>
```

```bash
$ cp .env.example .env
```

```bash
$ docker run --rm --interactive --tty --volume "$PWD":/app composer:latest install --ignore-platform-reqs
```

```bash
$ vendor/bin/sail up -d
```

```bash
$ vendor/bin/sail root-shell -c "chmod -R 777 storage"
```

```bash
$ vendor/bin/sail artisan key:generate
```

```bash
$ vendor/bin/sail ps
```

```bash
$ vendor/bin/sail php artisan migrate --seed

### Uso

- Aplicação: [http://localhost](app)

- Usuário já cadastrados
    - recepcionista@test.com
    - enfermeiro@test.com
    - medico@test.com
    - tecnico_enfermagem@test.com

    Todos tem como senha 12345678.