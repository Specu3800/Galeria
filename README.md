# Internet Gallery

Simple gallery allowing to upload photos, store them in album, rate and comment.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

In order to test page functionality one needs PHP and mySQL server. I used XAMPP to do it.
```
$ wget https://www.apachefriends.org/xampp-files/5.6.20/xampp-linux-x64-5.6.20-0-installer.run
$ sudo chmod +x xampp-linux-x64-5.6.20-0-installer.run
$ sudo ./xampp-linux-x64-5.6.20-0-installer.run
```

### Cloning

```
$ git clone https://github.com/msuliborski/internet-gallery
```

### Usage

Place files from repository to `htdocs` folder.
```
$ sudo cp internet-gallery /?????/htdocs
```

Visit `localhost/phpmyadmin` via you local browser and, create `suliborski` database and import `sql/suliborski.sql` file. Visiting `localhost/internet-gallery` in your local browser should display the whole page.

## Authors

* **Michał Suliborski** - [msuliborski](https://github.com/msuliborski)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details



