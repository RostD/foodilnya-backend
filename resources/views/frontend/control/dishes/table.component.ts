/**
 * Created by Ростислав on 17.01.2017.
 */
import {Component, OnInit, Input, Output, EventEmitter} from "@angular/core";

@Component({
    selector: 'dishes',
    templateUrl: '/app/control/dishes/view/table.component.html'
})
export class TableComponent implements OnInit {
    @Output() OnClickDish = new EventEmitter();
    @Input() dishes;


    loadModalData(dish) {
        this.OnClickDish.emit(dish);
    }

    ngOnInit():void {
    }

}
