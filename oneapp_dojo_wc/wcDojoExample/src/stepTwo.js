import '@webcomponents/webcomponentsjs';
import { LitElement, html, css } from 'lit-element';
import WcElement from './../../../shared/utils/wcElement';
import stylesScss from './styles.scss';
import axios from 'axios';

export class DojoExampleStepTwo extends WcElement {

    static get properties() {
        return {
            isLoading: { type: Boolean },
            data: { type: Object },
            config: { type: Object },
            showErrorMessage: {type: Boolean}
        };
    }

    static get styles() {
        return [stylesScss];
    }

    constructor() {
        super();
        this.isLoading = false;
        this.data = {};
        this.config = {};
        this.showErrorMessage = true;
    }


    renderData(key, data) {
        return html`
            <div class="stats-item">
                <span class="title">${key}:</span>
                <div class="value">${data}</div>
            </div>
        `;
    }
    
   
    attributeChangedCallback(name, oldValue, newValue) {
        super.attributeChangedCallback(name, oldValue, newValue);
        if(name == 'data' && oldValue != newValue){
            let data = JSON.parse(newValue);
            console.log(data);
            this.data = data.data;
            this.config = data.config;

        }
    }

    render() {
        return html`<div class="card-info">
            <div class="wrapper-block clearfix">
                <h3 class="title">${ this.config?.title?.value }</h3>   
                <div class="content clearfix">
                ${Object.keys(this.data).map((item, i) => true ? 
                    this.renderData(item, this.data[item]) 
                : ``)}  
                </div>
            </div>
        </div>`;
    }

    

    step(e, goto){
        if(goto == 1){
            this.sendSegmentTrack('Paperless Info Button Back Clicked');
        }
        this.emmitEvent('wcSteps', {goto: goto});
    }

    init() {
        
    }
 

    sendSegmentTrack(name, otherParams = {}){
        let params = {
            journey: 'dojo example users lists',
            linked_msisdns: this.wcData.id,
        };
        let trackParams = Object.assign({}, params, otherParams);
        this.sendInfoToSegment(name, trackParams);
    }
    
    createRenderRoot() { return this; }

}
