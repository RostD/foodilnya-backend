/// <reference path="../../cfg.ts"/>
import {Component, ViewChild} from "@angular/core";
import {DishService} from "./dish.service";
import {ModalComponent} from "./modal.component";
import {Dish} from "./dish";
import any = jasmine.any;


@Component({
    selector: 'my-app',
    templateUrl: _url('ngtmpl/control.dishes.my-app'),
    providers: [DishService],
})
export class AppComponent {
    @ViewChild(ModalComponent) modalWindow:ModalComponent;
    text:string;
    dishes:Dish[];

    constructor(private dishService:DishService) {
        this.loadDishes();
    }

    loadDishes() {
        this.dishService.getDishes().subscribe(
            (result) => this.dishes = result.data
        )
    }

    loadDishWindow(dish) {
        this.modalWindow.setDish(dish);
    }
    
}

