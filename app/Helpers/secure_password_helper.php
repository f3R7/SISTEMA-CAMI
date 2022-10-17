<?php

function hashPassowrd($texto){
	return password_hash($texto, PASSWORD_BCRYPT);
}

function verifyPassword($texto, $hash){
	return password_verify($texto, $hash);
}