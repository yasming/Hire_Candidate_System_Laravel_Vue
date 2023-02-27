# Hire_Candidate_System_Laravel_Vue

This is a project where we will have companies and candidates, where we as a company is able to hire candidates, with some rules: 

1. Contact a candidate feature which should consist of the following: when a company contacts a candidate, we should send an email to that candidate and charge the company 5 coins from its wallet.
2. When a company hires a candidate we should mark the candidate as hired, put back 5 coins in the wallet of the company, and send an email to the candidate to tell them they were hired. A company can hire only candidates that they have contacted before.

## Prerequisites

```
Docker
```

```
Composer >= 2.0
```

```
yarn >= 1.22
```

## Getting starting

1. To setup the environment follow the steps: 

```
composer install
```

```
./vendor/bin/sail up -d
```

```
./vendor/bin/sail artisan migrate --seed
```

```
yarn install
```

```
yarn dev
```

```
./vendor/bin/sail artisan queue:work
```

2. Now just go have fun hiring some candidates
