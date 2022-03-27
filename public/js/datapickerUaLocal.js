/* Локалізація datepicker */

function setUaLocale() {

	$.datepicker.regional['ukr'] = {
		closeText         : 'Закрити',
		prevText          : 'Попередній',
		nextText          : 'Наступний',
		currentText       : 'Сьогодні',
		monthNames        : ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'],
		monthNamesShort   : ['Січ', 'Лют', 'Бер', 'Кві', 'Тра', 'Чер', 'Лип', 'Сер', 'Вер', 'Жов', 'Лис', 'Гру'],
		dayNames          : ['неділя', 'понеділок', 'вівторок', 'середа', 'четвер', 'п’ятниця', 'субота'],
		dayNamesShort     : ['нед', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
		dayNamesMin       : ['Нд', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		weekHeader        : 'Тижд',
		dateFormat        : 'dd.mm.yy',
		firstDay          : 1,
		isRTL             : false,
		showMonthAfterYear: false,
		yearSuffix        : ''
	};
	$.datepicker.setDefaults($.datepicker.regional['ukr']);
}


setUaLocale();