<?php namespace djiney\crontab\components\traits;

trait LogTrait
{
	private static $_logFormat = 'Y-m-d H:i:s';

	/**
	 * Memory output with marks:
	 * self::loadMemory('log-mark');
	 *
	 * 2018-11-03 11:42:55 - [log-mark] Memory used: 2.79 MB
	 *
	 * @param $mark
	 */
	public static function loadMemory($mark = '')
	{
		if ($mark) {
			$mark = '[' . $mark . ']';
		}

		echo implode('', [
			date(self::$_logFormat),
			' - ',
			$mark,
			' Memory used: ',
			self::formatBytes(memory_get_usage()),
			PHP_EOL
		]);
	}

	/**
	 * Byte formatter:
	 * self::formatBytes(1541235240) // 1.44 GB
	 *
	 * @param $bytes
	 * @return float|string
	 */
	public static function formatBytes($bytes)
	{
		$bytes = floatval($bytes);
		$units = [
			'TB' => pow(1024, 4),
			'GB' => pow(1024, 3),
			'MB' => pow(1024, 2),
			'kB' => 1024,
			'B'  => 1,
		];

		foreach($units as $unit => $value) {
			if ($bytes >= $value) {
				return round($bytes / $value, 2) . ' ' . $unit;
			}
		}

		return $bytes;
	}

	/**
	 * Basic log output:
	 * self::log('logger test');
	 *
	 * 2018-11-03 11:23:17 - logger test;
	 *
	 * @param $text
	 */
	public static function log($text)
	{
		echo date(self::$_logFormat) . ' - ' . $text . PHP_EOL;
	}

	/**
	 * Basic log progress output:
	 * $i = 4;
	 * self::progress('test message', ++$i, 10);
	 *
	 * 2018-11-03 11:29:09 - test message 5 / 10
	 *
	 * @param      $text
	 * @param      $i
	 * @param      $count
	 * @param bool $percent
	 */
	public static function progress($text, $i, $count, $percent = false)
	{
		if ($count === 0 && $percent) {
			self::log('Count is invalid');
			$percent = false;
		}

		echo date(self::$_logFormat) .
			' - ' . $text . ' ' .
			($percent ? floor($i * 100 / $count ) . '%' : $i . ' / ' . $count) .
			PHP_EOL;
	}
}