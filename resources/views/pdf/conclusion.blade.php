<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=e, initial-scale=1.0">
    <title>Document</title>

    <style>
        *{
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body{
            margin: 30px 0 0 0;
            padding: 0 20px;
            font-size: 8px;
            line-height: 120%;
        }
        table{
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        table{
            border-width: 3px;
            margin: 15px 0;
        }
        .strong-border{
            border-width: 3px;
        }

        table th, table td{
            padding: 2px;
        }
        .clear-border-table,
        .clear-border-table th,
        .clear-border-table td{
            border: none;
        }
        .font-italic{
            font-style: italic;
        }
    
        .bg-green{
            background-color: #59a797;
        }
        .fz-2{
            font-size: 8px;
            line-height: 140%;
        }
        .text-underline{
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .text-no-wrap{
            white-space: nowrap;
        }
        
    </style>
</head>
<body>
    <div style="width: 130px; margin: 0 70px 10px auto;">
        <img style="width: 100%;" src="{{asset('img/components/logo.png')}}" alt="">            
    </div>
    <h2>Акт технічної експертизи № {{$conclusion->id}}</h2>
    <table class="clear-border-table" style="width: 100%; margin-top: 20px; margin-bottom: 10px;">
        <tbody>
            <tr>
                <td class="text-no-wrap" style="padding-right: 30px;">
                    <p>
                        Дата:
                    </p>
                </td>
                <td>
                    <p class="fz-2 text-no-wrap" style="line-height: 100%;">
                        <strong>{{now()->format('d.m.Y')}}</strong>
                    </p>
                </td>
                <td class="text-no-wrap" style="padding-left: 430px;">
                    <p>
                        Номер квитанції про приймання:
                    </p>
                </td>
                <td>
                    <p class="fz-2 text-no-wrap" style="line-height: 100%;">
                        <strong>{{$warrantyClaim->receipt_number}}</strong>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr>
                <td class="strong-border">
                    <div>
                        <p>Покупець:</p>
                        <p class="font-italic"><strong>{{$warrantyClaim->client_name}}</strong></p>
                    </div>
                    
                    <div style="margin: 30px 0;">
                        <p>Адреса: </p>
                        <p>{{$warrantyClaim->point_of_sale ?? 'Не вказано'}}</p> 
                    </div>
                    <div>
                        <p>Телефон:</p>
                        <p class="font-italic"><strong>{{$warrantyClaim->client_phone}}</strong></p>
                    </div>
                </td>
                <td class="strong-border" colspan="2">
                    <div>
                        <h2><strong>{{$warrantyClaim->client_name}}</strong></h2>
                    </div>

                    <div style=" margin: 30px 0;">
                        <p>Адреса:</p>
                        <p><strong>{{$warrantyClaim->point_of_sale ?? 'Не вказано'}}</strong></p>
                    </div>

                    <div>
                        <p>Телефон:</p>
                        <p><strong>{{$warrantyClaim->client_phone}}</strong></p>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="strong-border bg-green">
                    <p>Укладач:</p>
                </td>
                <td class="strong-border" colspan="2">
                    <p><strong>{{$warrantyClaim->manager->first_name_ru ?? 'Не вказано'}}</strong></p>
                </td>
            </tr>

            <tr>
                <td>
                    <p>Назва виробу: </p>
                    <p class="fz-2 font-italic">
                        <strong>
                            {{$warrantyClaim->product_name}}
                        </strong>
                    </p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                </td>
                <td>
                    <p>Артикульний номер:</p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                    <p class="fz-2 font-italic">
                        <strong>
                            {{$warrantyClaim->product_article}}
                        </strong>
                    </p>
                </td>
                <td>
                    <p>Робочі години виробу:</p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                </td>
            </tr>

            <tr>
                <td>
                    <p>Дата та місце продажу:</p>
                    <p class="fz-2 font-italic">
                        <strong>
                            {{now()->format('d.m.Y')}}
                        </strong>
                    </p>
                    <p class="fz-2 font-italic">
                        <strong>
                            {{$warrantyClaim->point_of_sale ?? 'Не вказано'}}
                        </strong>
                    </p>
                </td>
                <td>
                    <p>Серійний / заводський номер: </p>
                    <p class="fz-2 font-italic">
                        <strong>
                            {{$talon->factory_number}}
                        </strong>
                    </p>
                    <p class="fz-2 font-italic">
                        <strong>
                            {{$warrantyClaim->receipt_number}}
                        </strong>
                    </p>
                </td>
                <td>
                    <p>Дата звернення в СЦ:</p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                    <p class="fz-2 font-italic">
                        <strong>
                            {{$warrantyClaim->date_of_claim}}
                        </strong>
                    </p>
                </td>
            </tr>

            <tr>
                <td>
                    <p>Виробник двигуна: </p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                </td>
                <td>
                    <p>Назва моделі двигуна:</p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                </td>
                <td>
                    <p>Серійний номер двигуна:</p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                    {{-- <p class="fz-2 font-italic"><strong>Онлайн</strong></p> --}}
                </td>
            </tr>

            <tr>
                <td>
                    <p>Виробник коробки передач:</p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                </td>
                <td>
                    <p>Номер касового чеку: </p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                    <p class="fz-2 font-italic"><strong>&nbsp;</strong></p>
                </td>
                <td>
                    <p>Номер гарантійного талону:</p>
                    <p class="fz-2 font-italic"><strong>{{$talon->id}}</strong></p>
                    {{-- <p class="fz-2 font-italic"><strong>Онлайн</strong></p> --}}
                </td>
            </tr>

            <tr>
                <td class="bg-green strong-border" colspan="3">
                    <p>Опис дефекту</p>
                </td>
            </tr>

            <tr>
                <td class="strong-border" colspan="3" style="padding-bottom: 20px;">
                    <p>{{$warrantyClaim->details}}</p>
                </td>
            </tr>

            <tr>
                <td class="bg-green strong-border" colspan="3">
                    <p>Висновок</p>
                </td>
            </tr>

            <tr>
                <td class="strong-border" colspan="3" style="padding-bottom: 20px;">
                    <p>{{$conclusion->conclusion}}</p>
                </td>
            </tr>
        </tbody>
    </table>


    <table>
        <tbody>
            <tr>
                <td class="bg-green strong-border">
                    <p>Доповнення</p>    
                </td>
            </tr>
            <tr>
                <td>
                    <table class="clear-border-table" style="width: 80%; table-layout: fixed; margin: 50px auto 15px auto;">
                        <tbody>
                            <tr>
                                <td style="text-align: start;">
                                    <p class="text-no-wrap">
                                        Дата:
                                        <strong>______________________</span>
                                    </p>
                                </td>
                                <td style="text-align: end;">
                                    <p class="text-no-wrap">
                                        Печатка:
                                        <strong>______________________</span>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr>
                <td class="bg-green strong-border">
                    <p>Резолюція AL-KO</p>    
                </td>
            </tr>
            <tr>
                <td class="clear-border-table">
                    {{-- <p>{{$conclusion->appeal_type}}</p> --}}
                    <p>{{$conclusion->resolution}}</p>
                </td>
            </tr>
            <tr>
                <td class="clear-border-table">
                    <table class="clear-border-table" style="width: 80%; table-layout: fixed; margin: 10px auto 10px auto">
                        <tbody>
                            <tr>
                                <td style="text-align: start;">
                                    <p class="text-no-wrap">
                                        Дата:
                                        <span class="text-underline">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <strong>{{now()->format('d.m.Y')}}</strong>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </span>
                                    </p>
                                </td>
                                <td style="text-align: end;">
                                    <p class="text-no-wrap">
                                        Присвоєно резолюцію №: 
                                        <span class="text-underline">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <strong>{{$conclusion->id}}</strong>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </span>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg-green strong-border">
                    <p>Відмітка бухгалтерії AL-KO</p>    
                </td>
            </tr>
            <tr>
                <td>
                    <table class="clear-border-table" style="width: 80%; table-layout: fixed; margin: 40px auto 15px auto;">
                        <tbody>
                            <tr>
                                <td style="text-align: start;">
                                    <p class="text-no-wrap">
                                        Дата:
                                        <strong>______________________</span>
                                    </p>
                                </td>
                                <td style="text-align: end;">
                                    <p class="text-no-wrap">
                                        Підпис: 
                                        <strong>_______________________</span>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>