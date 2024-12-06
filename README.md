# HINTWAVE - Semestrální projekt ZWA
## WEB - https://zwa.toad.cz/~macenmic
## DOCS - https://zwa.toad.cz/~macenmic/public/docs

## Produktová dokumentace - [product-docs.md](product-docs.md)
## Technická dokumentace - [tech-docs.md](tech-docs.md)

## Vytvoření databázového dumpu

Pro vytvoření dumpu databáze `hintwave` použijte následující příkaz:

```bash
docker exec mysql mysqldump -u root -proot hintwave > db_dump.sql
```

## Obnovení databáze z dumpu

Pro obnovení databáze `hintwave` z vytvořeného dumpu použijte následující příkaz:
```bash
docker exec -i mysql mysql -u root -proot hintwave < db_dump.sql
```

