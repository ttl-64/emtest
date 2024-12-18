# Тестовое задание на позицию Junior Devops

Разработать ansible, запускающий докеризированное веб приложение (nginx, php, mysql) с использованием docker-compose.yml на удалённом сервере.
Подробнее в файле Task.txt
___
## Вводное слово

Здравствуйте! 
В качестве тестовой среды (удаленного сервера) использовал VM, поднятую с помощью Vagrant + Virtual Box.
В качестве VM box использовался образ ubuntu/focal64, но предусмотрел в ansible-playbook возможность воспроизведения на других ОС.

## Оглавление

1. [Установка и описание необходимых компонентов](#установка-и-описание-необходимых-компонентов)
2. [Клонирование репозитория](#клонирование-репозитория)
3. [Дерево репозитория](#дерево-репозитория)
4. [Подготовка среды](#подготовка-среды)
5. [Запуск ansible-playbook](#запуск-ansible-playbook)
6. [Описание решения](#описание-решения)
7. [Чек-лист](#чек-лист)


## Установка и описание необходимых компонентов

Для проверки работы решения требуется установка следующих компонентов:
* [Vagrant](https://developer.hashicorp.com/vagrant/install?product_intent=vagrant)
* [Virtual Box 7.0](https://www.virtualbox.org/wiki/Downloads)
* [Ansible](https://docs.ansible.com/ansible/latest/installation_guide/installation_distros.html)
* [Ansible-module community.docker](https://galaxy.ansible.com/ui/repo/published/community/docker/)
* [Git](https://git-scm.com/downloads)

### Используемые docker-образы в проекте
* [Nginx](https://hub.docker.com/layers/library/nginx/alpine/images/sha256-ae136e431e76e12e5d84979ea5e2ffff4dd9589c2435c8bb9e33e6c3960111d3?context=explore)
* [PHP-FPM](https://hub.docker.com/layers/library/php/8.2-fpm/images/sha256-d22521c5a777b857dd9a5630872c4b1b4d3640b23791bea98ef03af35a59c4fd?context=explore)
* [MySQL](https://hub.docker.com/layers/library/mysql/9.0/images/sha256-0cf8b60f74e235c4dd4beb762b10ec6da3dce30265f5f1d40c2c11fa7872ebd9?context=explore)

### Используемые сетевые порты
|  Vagrant| Service    | Port |
|---------|------------|------|
|         | PHP        | 9000 |
|8080 > 80| Nginx      | 80   |

## Клонирование репозитория

Для клонирования репозитория:
```sh
git clone https://github.com/ttl-64/emtest.git
```

## Дерево репозитория
```
./
├── playbook
│   ├── ansible.cfg
│   ├── inventory
│   │   └── hosts.ini
│   ├── roles
│   │   └── webservers
│   │       ├── files
│   │       │   ├── .env
│   │       │   ├── docker-compose.yml
│   │       │   ├── Dockerfile
│   │       │   ├── index.html
│   │       │   └── index.php
│   │       ├── handlers
│   │       │   └── main.yml
│   │       ├── tasks
│   │       │   ├── centos.yml
│   │       │   ├── debian.yml
│   │       │   ├── main.yml
│   │       │   └── ubuntu.yml
│   │       ├── templates
│   │       │   └── nginx.conf.j2
│   │       └── vars
│   │           └── main.yml
│   └── webserver.yml
├── README.md
├── Task.txt
├── tips
└── Vagrantfile
```

## Подготовка среды

Обязательно убедитесь в наличии установленного Vagrant и Virtual Box

```sh
cd emtest
vagrant up
```
Проброс портов в Vagrantfile:
- vagrant1.vm.network "forwarded_port", guest: 80, host: 8080
- vagrant1.vm.network "forwarded_port", guest: 443, host: 8443
(конечно можно было бы сразу в Vagrantfile описать провижонинг с ansible сценарием)

## Запуск ansible-playbook

```sh
cd playbook
ansible-galaxy collection install community.docker
ansible-playbook webserver.yml --ask-vault-pass
123
```

## Описание решения
Образы для контейнеров nginx и mysqldb берутся готовые из репозитория docker.io.
Образ для контейнера php82fpm собирается на удаленном сервере с помощью Dockerfile.
Запуск сервисов осуществляется с помощью docker-compose.

В результате выполнения сценария получаем на удаленном сервере 3 запущенных контейнера: nginx, php82fpm, mysqldb в одной сети - webapp.

Порты 80,443 у nginx и 9000 у php82fpm проброшены на хостовую машину 1-в-1.

Для проверки работы воспользуйтесь браузером:
* [http://localhost:8080/index.html](http://localhost:8080/index.html)
* [http://localhost:8080/index.php](http://localhost:8080/index.php)

При переходе по последней ссылке проверяем корректно работующую интеграцию с БД mysql.

## Чек-лист

**Плейбук должен:**
- :white_check_mark: Дистрибьютить необходимые для работы файлы
- :white_check_mark: Генерировать конфигурационный файл для nginx, и, по необходимости,
перезапускать его (nginx)

**Результатом выполнения ТЗ является:**
- :white_check_mark: Ansible playbook (вместе с ролями и/или файлом с зависимостями, необходимыми
шаблонами)
- :white_check_mark: docker-compose.yml
- :white_check_mark: Dockerfile
___
Буду благодарен обратной связи!