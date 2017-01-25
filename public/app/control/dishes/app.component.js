System.register(["@angular/core", "./dish.service", "./modal.component"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
            var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
            if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
            else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
            return c > 3 && r && Object.defineProperty(target, key, r), r;
        };
    var __metadata = (this && this.__metadata) || function (k, v) {
            if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
        };
    var core_1, dish_service_1, modal_component_1;
    var any, AppComponent;
    return {
        setters: [
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (dish_service_1_1) {
                dish_service_1 = dish_service_1_1;
            },
            function (modal_component_1_1) {
                modal_component_1 = modal_component_1_1;
            }],
        execute: function () {
            AppComponent = (function () {
                function AppComponent(dishService) {
                    this.dishService = dishService;
                    this.loadDishes();
                }
                AppComponent.prototype.loadDishes = function () {
                    var _this = this;
                    this.dishService.getDishes().subscribe(function (result) {
                        return _this.dishes = result.data;
                    });
                };
                AppComponent.prototype.loadDishWindow = function (dish) {
                    this.modalWindow.setDish(dish);
                };
                __decorate([
                    core_1.ViewChild(modal_component_1.ModalComponent), 
                    __metadata('design:type', modal_component_1.ModalComponent)
                ], AppComponent.prototype, "modalWindow", void 0);
                AppComponent = __decorate([
                    core_1.Component({
                        selector: 'my-app',
                        templateUrl: _url('ngtmpl/control.dishes.my-app'),
                        providers: [dish_service_1.DishService],
                    }), 
                    __metadata('design:paramtypes', [dish_service_1.DishService])
                ], AppComponent);
                return AppComponent;
            }());
            exports_1("AppComponent", AppComponent);
        }
    }
});

//# sourceMappingURL=app.component.js.map
