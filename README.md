# Integrační rozhraní RAYNET CRM
Tento repozitář obsauje ukázku využití integračního API k Vašemu RAYNET CRM. Každá služba obsahuje složku example, jejíž obsahem je velmi jednoduchý PHP skript implementující službu v plném rozsahu. Požadavky na server pro spuštění příkladu jsou PHP ve verzi 5+ s aktivovanou knihovnou curl (https://php.net/curl).

Pro implementaci doporučujeme využít třídu *Raynetcrm*, která implementuje veškeré dostupné služby s velmi jednoduchým rozhraním. Veškerá práce se poté redukuje na pouhý include souboru *Raynetcrm.php* s následným vytvořením instance třídy *Raynetcrm*. V constructoru třídy je potřeba vyplnit název Vaší instance RAYNET CRM. Pro integrační službu je nutné mít ve Vašem RAYNET CRM vytvořeného uživatele, který bude sloužit právě pro vytváření resp. editaci záznamů. Přihlašovací údaje jsou další atributy constructoru. Namísto hesla je nutno zadat API klíč, který je možné vygenerovat v aplikaci RAYNET CRM (Detail uživatele -> Oprávnění uživatele -> Změnit oprávnění -> Vygenerovat nový API klíč). Po vytvoření instance je potřeba pouze volat příslušné integrační služby (např. insertLead pro vytvoření zájemce).

Taktéž můžete využít vlastní třídu pro implementaci API, rozhraní k RAYNET CRM funguje na základních principech REST. Pro bližší informace doporučujeme prozkoumat třídu *Raynetcrm*.
