# مرحله بعد: سرور پخش

پنل برای اتصال Media Engine آماده است. مرحله بعد پیشنهادی:

1. Ubuntu LTS
2. Nginx
3. FFmpeg
4. MediaMTX
5. systemd services برای Playout workers
6. Redis برای queue/state در صورت نیاز
7. HLS Origin
8. Storage مستقل برای ویدئو
9. CDN
10. Health checks و Prometheus-compatible metrics

پنل از طریق StreamEngineInterface به این لایه وصل می‌شود. تا قبل از اتصال، `NullStreamEngine` مانع وابستگی پنل به سرویس خارجی است.
