<?php

namespace App\Enum;

enum RentalStatusEnum: string
{
    case EN_ATTENTE_VALIDATION = 'en_attente_validation'; 
    case VALIDEE = 'validée';                             
    case REFUSEE = 'refusée';                             
    case EN_COURS = 'en_cours';                           
    case TERMINEE = 'terminée';                           
    case ANNULEE = 'annulée';                            
    case EXPIREE = 'expirée'; 
    case DEMANDE_ANNULEE = 'demande_annulée';                          
}
