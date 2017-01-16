import {platformBrowserDynamic} from "@angular/platform-browser-dynamic";
import {AppModule} from "./app.module";

// ПОДРУБАЕМ МОДУЛИ, В КОТОРОМ ЗАГРУЖЕНЫ НУЖНЫЕ НАМ КОМПОНЕНТЫ

const platform = platformBrowserDynamic();

//ЗАПРАВЛЯЕМ МОДУЛЬ ДЛЯ ВЫВОДА В БРАУЗЕР
//noinspection TypeScriptValidateTypes
platform.bootstrapModule(AppModule);