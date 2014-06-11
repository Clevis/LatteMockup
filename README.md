LatteMockup
===========

Renderování libovolné latte šablony.

## Použití

Instalace přes `composer.json`

```
"clevis/template-preview": "@dev"
```

```
repositories: [{
	"type": "vcs",
	"url": "https://github.com/Clevis/LatteMockup",
}]
```

Není potřeba cokoliv nastavovat. Tento nástroj si sám najde všechny proměnné, které šablona používá. Při použití webového prostředí
se automaticky najdou i layouts (bere se první `@layout` ve složce šablony, případně nadsložce atd.).

Šablony lze buď generovat v konzolovém nástroji `./vendor/bin/render`. Druhá možnost je klikací webové prostředí, které
se defaultně nechází v `./vendor/clevis/template-preview/index.php`. Doporučuji si udělat symlink pod www, aby byl soubor
na localhostu dobře přístupný. To vám také umožní načítat závislosti jako jsou css a js soubory. Pokud používáte na
localhostu Apache, symlink musí být absolutní a možná budete potřebovat do `.htaccess` ve `www` přidat `Options +FollowSymLinks`.
V Nginx vám to poběží v pohodě rovnou.

## Rozšíření

Podle názvu proměnné, resp. parametru, se volá faker s českými providery (https://github.com/fzaninotto/Faker).
Hledá se od posledního názvu: pro {$a->b['c']} se zkusí najít provider s názvem `c`, potom `b` a případně `a`.
Pokud se žádný vhodný generátor nenajde (jako by se našel např. pro `{$user->firstName}`), zobrazí se náhodný text.

Pokud chcete rozšířit jaké názvy faker rozpozná, editujte `Clevis\TemplatePreview\MockProvider`.

## Limitace

Protože je vykreslování úplně nezávislé na aplikaci, nevykreslují se formuláře, obecně žádné komponenty. Pokud definuje aplikace
vlastní filtery (helpery) nebo makra, není jak je rozpoznat.

## Motivace

Zobrazení a otestování nette šablon bez nutnosti mít spuštěnou aplikaci.

Pro každou šablonu a její stav budeme mít něco jako mockup, který nebude závislý na stavu aplikace a zobrazené údaje bude mít připravené staticky dopředu. Tedy statické vykreslení.

Ideálně pak budu mít rovnou na každém projektu rozcestník se všemi šablonami a jejími stavy, kam se dostanu na jedno kliknutí.

Odhaduji, že to dokáže ušetřit jednotky až desítky procent času stráveného na finální fázi kódování a testování.

## Zadání

1. Rozcestník šablon a jejich stavů
2. Zobrazení definovaného stavu šablony

### 1. Rozcestník šablon

Stránka s automaticky generovaným seznamem šablon v aplikaci.

U šablon s konfiguračním souborem zobrazit také její stavy.

Každá položka vede A) na samotnou šablonu (když nemá mockup) nebo B) na zobrazení stavu šablony

### 2. Zobrazení stavu šablony

Šablona má svůj konfigurační soubor, ve kterém jsou specifikované její stavy s odpovídajícími testovacími daty. Data by měla jít mezi stavy dědit - tedy mám jeden výchozí stav default, který přepisuji odvozenými stavy.
Při zobrazení daného stavu se tedy těmito daty šablona naplní.

Předpokládáme jednoduché šablony s výpisem proměnných a kolekcí proměnných.

Nice-to-have: Formuláře.

Note: Vilíkovo Ristretto už dělá 1/3 toho, co chceme.
