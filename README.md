## RPKI web validator

[demo site https://rpki.bg6cq.cn](https://rpki.bg6cq.cn)

## tested install steps

1. install Ubuntu

2. install apache/php etc.
```
sudo apt-get update
sudo apt-get install -y apache2 php php-curl gcc git
```

3. install and run Routinator

[Routinator](https://www.nlnetlabs.nl/projects/rpki/routinator/)

```
curl https://sh.rustup.rs -sSf | sh
source ~/.cargo/env
cargo install routinator
routinator init
# Follow instructions provided
routinator server --rtr 127.0.0.1:3323 --http 127.0.0.1:9556
```

4. copy index.php validity.php routinator.php to web dir

now you can access http://x.x.x.x/web
