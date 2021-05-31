//Web Components Polyfills
import '@webcomponents/webcomponentsjs';

//clase de la cual heredan todos los web components con caracteristicas comunes
import WcElement from './../../../shared/utils/wcElement';

// importando estilos y html
import stylesScss from './styles.scss';

import { LitElement, html, css } from 'lit-element';

import { DojoExampleStepOne } from './stepOne';
import { DojoExampleStepTwo } from './stepTwo';

class WCMitigoDojoExample extends WcElement {

    static get scopedElements() {
        return {
            'dojo-example-step-one': DojoExampleStepOne,
            'dojo-example-step-two': DojoExampleStepTwo,
        };
    }

    static get properties() {
        return {
            currentStep: { type: Number },
            steps: { type: Array },
            wcData: { type: Object },
            datastep: {type: Object}
        };
    }

    static get styles() {
        return [stylesScss];
    }

    constructor() {
        super();
        this.currentStep = 1;
        this.datastep = {};
        this.isLoadingFont = false;
        
        var WebFont = require('webfontloader');
        WebFont.load({
            custom: {
                families: ['icoTigoUne', 'robotoregular', 'robotomedium'],
                urls: ['https://atomic.tigocloud.net/sandbox/css/main-v1.1.0.min.css']
            },
            loading: () => { this.isLoadingFont = true;},
            active: () => { this.isLoadingFont = false;},
        });
    }

    render() {
        return html`
        <link rel="stylesheet" href="https://atomic.tigocloud.net/sandbox/css/main-v1.1.0.min.css">
        <div class="root-element ${(this.isHide && this.isLoadingFont) ? 'hidden': '' }">
            <div class="ml-card-big cc">
                <div class="at-containershadow-primary">
                    <div class="content-card">
                        ${this.currentStep == 1 ? html`<dojo-example-step-one .wcData="${this.wcData}" @wcSteps="${this.steps}"></dojo-example-step-one>` : ''}
                        ${this.currentStep == 2 ? html`<dojo-example-step-two .wcData="${this.wcData}" @wcSteps="${this.steps}" data="${this.datastep}"></dojo-example-step-two>` : ''}
                    </div>
                </div>
            </div>
        </div>`;
    }

    steps(e){
        const data = e.detail.data;
        if(data.goto == 2){
            this.datastep = JSON.stringify(data.data);
        }
        this.currentStep = data.goto;
    }

    init() {
        this.isHide = false;
        console.log('WCDATA', this.wcData);
    }
}
// Definición de tag html.
window.customElements.define('wc-mitigo-dojo-example-users-list', WCMitigoDojoExample);

