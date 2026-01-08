<p align="center">
  <h1 align="center">Rezervační systém</h1>
</p>

<p align="center">
  <b>Online verze projektu:</b>
  <a href="http://rezervace-mulac.wz.cz">http://rezervace-mulac.wz.cz</a>
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
- [O aplikaci](#o-aplikaci)
- [Struktura projektu](#struktura-projektu)
- [Přispívání](#příspívání)
- [Licence](#licence)
- [Kontakt](#Kontakt)

---

## Funkce 
> (dle `Zadání.pdf` a něco navíc)

- Vytváření, úprava a mazání rezervací  
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

- Otevřete složku `htdocs`, tam kde máte nainstalovaný XAMPP
- Naklonujte repo z GitHubu
- Nahrajte DB `rezervace` na localhost
- Změňte `config\config.php` podle názvu vaší DB na `localhost`

```git
cd C:\xampp\htdocs
git clone https://github.com/Mareczech321/Rezervace.git
```

### Externí server / hosting

- Nahrajte soubory podle instrukcí hostingu
- Změňte `config\config.php` na přihlašovací údaje na DB hostingu

---

## O aplikaci

- Sekce:
  - Rezervované místnosti - seznam obsazených místností seřezených podle času
  - Seznam místností - seznam místností a jejich kapacita
  - Správa rezervací
- Mazaní rezervací:
  - Přes `id` (`Správa rezervací`)
  - Přímo v tabulce rezervací (možnost mazat i zaheslované rezervace)
- Přidávání rezervací (`Správa rezervací`):
  - Nepřihlášený uživatel:
    - Může přidat rezervaci a zaheslovat ji
  - Přihlášený uživatel:
    - To samé co nepřihlášení uživatel
    + Rezervace budou automaticky odemknuty

---

## Struktura projektu

~~~plaintext
└── 📁Rezervace
    ├── 📁account
        ├── index.php
    ├── 📁config
        ├── config.php
        ├── db.php
    ├── 📁img
    └── 📁sprava
        ├── add.php
        ├── delete.php
        ├── unlock.php
        ├── upravit.php
    ├── .gitattributes
    ├── index.php
    ├── logout.php
    ├── readme.md
    ├── rezervace.sql
    ├── style.css
    └── Zadani.pdf
~~~

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
  
Problémy a návrhy: [GitHub Issues](https://github.com/Mareczech321/Rezervace/issues)
