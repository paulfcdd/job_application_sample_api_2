start:
	docker compose up -d

down:
	docker compose down

ssh:
	docker compose exec app bash

db:
	docker compose exec db bash