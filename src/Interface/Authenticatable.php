<?php
namespace Memory\App\Interface;

Interface Authenticatable {

	public static function logIn(string $login, string $password): ?self;

	public static function signUp(array $data): bool;

	public static function logOff(): void;
}