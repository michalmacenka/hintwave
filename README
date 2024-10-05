# Databázový dump a obnova
Tento projekt používá MySQL databázi, kterou spravujeme pomocí Dockeru. Níže jsou uvedeny kroky, jak vytvořit dump databáze a jak obnovit databázi z dumpu.

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

