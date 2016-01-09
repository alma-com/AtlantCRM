<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "Вы должны принять \":attribute\".",
	"active_url"     => "Поле \":attribute\" должно быть полным URL.",
	"after"          => "Поле \":attribute\" должно быть датой после \":date\".",
	"alpha"          => "Поле \":attribute\" может содержать только буквы.",
	"alpha_dash"     => "Поле \":attribute\" может содержать только буквы, цифры и тире.",
	"alpha_num"      => "Поле \":attribute\" может содержать только буквы и цифры.",
	"array"                => "Поле \":attribute\" должено быть массивом.",
	"before"         => "Поле \":attribute\" должно быть датой перед \":date\".",
	"between"              => [
		"numeric" => "Поле \":attribute\" должно быть между :min и :max.",
		"file"    => "Поле \":attribute\" должно быть от :min до :max Килобайт.",
		"string"  => "Поле \":attribute\" должно быть от :min до :max символов.",
		"array"   => "Поле \":attribute\" должно быть между :min и :max.",
	],
	"boolean"              => "Поле \":attribute\" должно быть правдой или ложью.",
	"confirmed"      => "Поле \":attribute\" не совпадает с подтверждением.",
	"date"                 => "Поле \":attribute\" должно быть датой.",
	"date_format"          => "Поле \":attribute\" не является форматом даты :format.",
	"different"      => "Поля \":attribute\" и \":other\" должны различаться.",
	"digits"               => "Поле \":attribute\" должно быть :digits цифрой.",
	"digits_between"       => "Поле \":attribute\" должно быть между :min и :max.",
	"email"          => "Поле \":attribute\" имеет неверный формат.",
	"filled"               => "Поле \":attribute\" обязательно для заполнения.",
	"exists"         => "Выбранное значение для \":attribute\" уже существует.",
	"image"          => "Поле \":attribute\" должно быть картинкой.",
	"in"             => "Выбранное значение для \":attribute\" не верно.",
	"integer"        => "Поле \":attribute\" должно быть целым числом.",
	"ip"             => "Поле \":attribute\" должно быть полным IP-адресом.",
	"\":max\""                  => [
		"numeric" => "Поле \":attribute\" должно быть меньше :max.",
		"file"    => "Поле \":attribute\" должно быть меньше :max Килобайт.",
		"string"  => "Поле \":attribute\" должно быть короче :max символов.",
		"array"   => "Поле \":attribute\" должно быть меньше :max.",
	],
	"mimes"          => "Поле \":attribute\" должно быть файлом одного из типов: \":values\".",
	"min"                  => [
		"numeric" => "Поле \":attribute\" должно быть не менее :min.",
		"file"    => "Поле \":attribute\" должно быть не менее :min Килобайт.",
		"string"  => "Поле \":attribute\" должно быть не менее :min символов.",
		"array"   => "Поле \":attribute\" должно быть не менее :min.",
	],
	"not_in"         => "Выбранное значение для \":attribute\" не верно.",
	"numeric"        => "Поле \":attribute\" должно быть числом.",
	"regex"                => "Поле \":attribute\" имеет неверный формат.",
	"required"       => "Поле \":attribute\" обязательно для заполнения.",
	"required_if"          => "Поле \":attribute\" обязательно для заполнения когда \":other\" равен :value.",
	"required_with"        => "Поле \":attribute\" обязательно для заполнения когда \":values\" присутствует.",
	"required_with_all"    => "Поле \":attribute\" обязательно для заполнения когда  \":values\" присутствует.",
	"required_without"     => "Поле \":attribute\" обязательно для заполнения когда \":values\" не присутствует.",
	"required_without_all" => "Поле \":attribute\" обязательно для заполнения когда \":values\" не присутствует.",
	"same"           => "Значение \":attribute\" должно совпадать с \":other\".",
	"size"                 => [
		"numeric" => "Поле \":attribute\" должно быть \":size\".",
		"file"    => "Поле \":attribute\" должно быть \":size\" Килобайт.",
		"string"  => "Поле \":attribute\" должно быть длиной \":size\" символов.",
		"array"   => "Поле \":attribute\" должно быть \":size\".",
	],
	"unique"         => "Такое значение поля \":attribute\" уже существует.",
	"url"            => "Поле \":attribute\" имеет неверный формат.",
	"timezone"             => "Поле \":attribute\" имеет неверный формат.",
	
	"recaptcha" => 'Необходимо разгадать капчу',

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'custom-message',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [
		'name' => 'Имя',
		'email' => 'E-mail',
		'password' => 'Пароль',
		'password_confirmation' => 'Еще раз пароль',
	],

];
