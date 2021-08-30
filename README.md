# Composant Notifier

Symfony 5 comprend également un nouveau composant **Notifier** pour créer et envoyer toutes sortes de notifications via SMS, e-mail et services de chat comme Slack et Telegram.


Le composant **notifier** peut envoyer des notifications à différents canaux. Chaque canal peut s’intégrer à différents fournisseurs (par exemple Slack ou Twilio SMS) en utilisant des transports.

Le composant notificateur prend en charge les canaux suivants:

* SMS envoie des notifications aux téléphones via des messages SMS
* Le chat envoie des notifications aux services de chat comme Slack et Telegram
* Le courrier électronique intègre Symfony Mailer
* Le navigateur utilise des messages flash
	

## Installation

Chargement de la dépendance via composer

`
composer require symfony/notifier
`

## Configuration

### Mail

Pour pouvoir envoyer des notifications par mail, il faudra configurer le *MAILER_DSN* dans le fichier *.env*:

`
MAILER_DSN=smtp://XXX:XXX@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login
`

Le canal de notification *email* nécessite les dépendances suivantes:

`
composer require symfony/twig-pack twig/cssinliner-extra twig/inky-extra
`

Enfin, on configurera le *mailer* dans le fichier *config/packages/mailer.yml*:

```
# config/packages/mailer.yaml
framework:
    mailer:
        dsn: '%env(MAILER_DSN)%'
        envelope:
            sender: 'sender@test.fr'

```

***

### SMS

https://symfony.com/doc/current/notifier.html#sms-channel

Le canal *SMS* utilise la classe *Symfony\Component\Notifier\Texter*.

Il aura besoin d'une librairie tierce pour procéder aux envois.

#### Avec Twilio

On devra installer la dépendance *symfony/twilio-notifier*:
`
composer require symfony/twilio-notifier
`

##### Configuration dans le fichier .env
`
TWILIO_DSN=twilio://XXX:XXX@default?from=+12152533638
`

##### Configuration du canal

```
# config/packages/notifier.yaml
framework:
    notifier:
        texter_transports:
            twilio: '%env(TWILIO_DSN)%'
```

***

### Chat

#### Avec Discord

##### Dépendance:

`
composer require symfony/discord-notifier
`

##### Configuration dans *.env*:

`
DISCORD_DSN=discord://XXX@default?webhook_id=XXX
`

##### Configuration du canal:

```
# config/packages/notifier.yaml
framework:
    notifier:
        chatter_transports:
            discord: '%env(DISCORD_DSN)%'
```

***

#### Avec Slack

Il faudra au préalable configurer le slack!

##### Dépendance:

`
composer require symfony/slack-notifier
`

##### Configuration dans *.env*:

`
SLACK_DSN=https://XXX.slack.com/archives/XXXX
`

##### Configuration du canal:

```
# config/packages/notifier.yaml
framework:
    notifier:
        chatter_transports:
            discord: '%env(DISCORD_DSN)%'
            slack: '%env(SLACK_DSN)%'
        #    telegram: '%env(TELEGRAM_DSN)%'
```

***

## Utilisation

On crée un contrôleur *OrderController*

`
symfony console make:controller
`

Et dans une action du controller, on peut injecter la dépendance *NotifierInterface*:

```
/**
     * @Route("/order/create", name="order_create")
     */
    public function create(NotifierInterface $notifier): Response
    {
```

Puis, créer une *Notification*:

```
		/*
            Notification pour prévenir d'une nouvelle commande
        */
            // Création d'une notification avec un sujet et un canal            
            $notification = new Notification('Nouvelle commande', ['email', 'sms', 'chat']);
            // Le tableau de canal définit comment la notification va être distribuée
            // ['email', 'sms']  enverrait à a fois par mail ET par SMS

            // Ajout du contenu à la notification
            $notification->content('Une nouvelle commande vient d\être passée');

            // On peut aussi ajouter des emojis
            $notification->emoji('💀');

```

Ainsi qu'un *Recipient*:

```
		/*
            Destinataire de la notification
        */
            // Création du destinataire
            $recipient = new Recipient(
                'destinataire@test.fr',
                '0633111111'
            );
```

Et enfin, procéder à l'envoi:

```
	/*
		Envoi de la notification
	*/
		$notifier->send($notification, $recipient);
```

***

On peut également utiliser ce composant via les **events** de Symfony.


Doc officielle:
https://symfony.com/doc/current/notifier.html


Livre Symfony 5:
https://symfony.com/doc/current/the-fast-track/fr/25-notifier.html#sending-web-application-notifications-in-the-browser

Projet Github:
https://github.com/LiliwoL/symfony5-notifier