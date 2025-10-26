<p align="center">
  <h1 align="center">Rezervační systém</h1>
</p>

<p align="center">
  🌐 <b>Online verze projektu:</b>
  <a href="http://rezervace-mulac.wz.cz:8080">http://rezervace-mulac.wz.cz:8080</a>
</p>

<div align="center">
  <img src="https://img.shields.io/badge/jazyk-PHP-blue.svg" />
  <img src="https://img.shields.io/badge/databaze-MySQL-green.svg" />
  <img src="https://img.shields.io/badge/stav-aktivní-brightgreen.svg" />
  <img src="https://img.shields.io/github/last-commit/Mareczech321/Rezervace.svg" />
</div>

---

## Obsah

- [Funkce](#funkce)
- [Požadavky a instalace](#funkce)
  - [Požadavky](#požadavky)
  - [Instalace](#instalace)
- [Použití](#použití)
- [Struktura projektu](#struktura-projektu)
- [Možné změny](#Možné-změny)
- [Přispívání](#příspívání)
- [Licence](#licence)
- [Kontakt](#Kontakt)

---

## Funkce (dle `Zadání.pdf` a něco navíc)

- Vytváření a mazání rezervací  
- Přehled dostupných a obsazených termínů  
- Přehledné a responzivní uživatelské rozhraní  

---

## Požadavky a instalace

### Požadavky

- PHP v. 7.4 nebo novější
- MySQL nebo jiná DB
- Server - localhost (např. XAMPP) nebo hosting

---

## Instalace

### XAMPP

- Otevřete složku `htdocs`, tam kde je nainstalovaný XAMPP
- Naklonujte repo z GitHubu
- Nahrajte DB na localhost

~~~git
cd C:\xampp\htdocs
git clone https://github.com/Mareczech321/Rezervace.git
~~~

### Externí server / hosting

- Nahrajte soubory podle instrukcí hostingu
- Změňte `config\config.php` na přihlašovací údaje na DB hostingu

---

## Použití

### XAMPP

- Spusťte XAMPP
- Přejděte na `http://localhost/Rezervace`

### Hosting

- Přejděte na URL vaší stránky

---

## Struktura projektu

~~~plaintext
└── 📁Rezervace
    └── 📁config
        ├── config.php
        ├── db.php
    ├── .gitattributes
    ├── index.php
    ├── readme.md
    ├── rezervace.sql
    ├── style.css
    └── Zadani.pdf
~~~

---

## Možné změny

- Přihlašovací systém
  - Login / registrace
  - Administrace
- Úprava příspěvků
  - Přihlášenými uživateli nebo heslem
- Notifikace přes e-mail
- Pokročilé filtrování, vyhledávání v případě větší DB

---

## Příspívání

1. Forkněte repo
2. Vytvořte novou větev
3. Proveďte změny
4. Pushněte větev

---

## Licence

Projekt **Rezervace** je licencován pod licencí **MIT**.  
Podrobnosti naleznete v souboru `LICENSE`.

---

## Kontakt

[@Mareczech321](https://github.com/Mareczech321)  
Problémy a návrhy: [GitHub Issues](https://github.com/Mareczech321/Rezervace/issues)
