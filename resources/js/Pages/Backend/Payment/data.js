import ghana_at from '@/Icons/ghana-at.png'
import ghana_vodafone from '@/Icons/ghana-vodafone.jpg'
import senegal from '@/Icons/senegal.png'
import ivory_coast from '@/Icons/ivory-coast.png'
import ghana from '@/Icons/ghana.png'
import cameroon_mtn from '@/Icons/cameroon-mtn.png'
import cameroon_orange from '@/Icons/cameroon-orange.png'
import cameroon from '@/Icons/cameroon.png'
import benin from '@/Icons/benin.png'
/**
 * currency
 XOF = Ivory Coast
 XAF = Cameroon
 GH₵ = Ghana
*/

export const countries = [
    // {
    //     name: 'cameroon',
    //     flag: cameroon,
    //     rate: 656,
    //     sim: [
    //         {
    //             mno: 'MTN',
    //             correspondent: 'MTN_MOMO_CMR',
    //             country: 'CMR',
    //             currency: 'XAF',
    //             prefix: '237',
    //             rate: 656,
    //             decimal: 0,
    //         },
    //         {
    //             mno: 'Orange',
    //             correspondent: 'ORANGE_CMR',
    //             country: 'CMR',
    //             currency: 'XAF',
    //             prefix: '237',
    //             rate: 656,
    //             decimal: 0,
    //         },
    //     ],
    // },
    {
        name: 'ivory_coast',
        flag: ivory_coast,
        rate: 656,
        sim: [
            {
                mno: 'MTN',
                correspondent: 'MTN_MOMO_CIV',
                country: 'CIV',
                currency: 'XOF',
                prefix: '225',
                rate: 656,
                decimal: 0,
            },
            {
                mno: 'Orange',
                correspondent: 'ORANGE_CIV',
                country: 'CIV',
                currency: 'XOF',
                prefix: '225',
                rate: 656,
                decimal: 0,
            },
        ]
    },
    // {
    //     name: 'ghana',
    //     flag: ghana,
    //     rate: 12.8,
    //     sim: [
    //         {
    //             mno: 'MTN',
    //             correspondent: 'MTN_MOMO_GHA',
    //             country: 'GHA',
    //             currency: 'GHS',
    //             prefix: '233',
    //             rate: 12.8,
    //             decimal: 2,
    //         },
    //         {
    //             mno: 'AT',
    //             correspondent: 'AIRTELTIGO_GHA',
    //             country: 'GHA',
    //             currency: 'GHS',
    //             prefix: '233',
    //             rate: 12.8,
    //             decimal: 2,
    //         },
    //         {
    //             mno: 'Vodafone',
    //             correspondent: 'VODAFONE_GHA',
    //             country: 'GHA',
    //             currency: 'GHS',
    //             prefix: '233',
    //             rate: 12.8,
    //             decimal: 2,
    //         },
    //     ]
    // },
    {
        name: 'senegal',
        flag: senegal,
        rate: 656,
        sim: [
            {
                mno: 'Free',
                correspondent: 'FREE_SEN',
                country: 'SEN',
                currency: 'XOF',
                prefix: '221',
                rate: 656,
                decimal: 0,
            },
            {
                mno: 'Orange',
                correspondent: 'ORANGE_SEN',
                country: 'SEN',
                currency: 'XOF',
                prefix: '221',
                rate: 656,
                decimal: 0,
            },
        ]
    },
    {
        name: 'cameroon',
        flag: cameroon,
        rate: 656,
        sim: [
            {
                mno: 'MTN',
                correspondent: 'MTN_MOMO_CMR',
                country: 'CMR',
                currency: 'XAF',
                prefix: '237',
                rate: 656,
                decimal: 0,
            },
            {
                mno: 'Orange',
                correspondent: 'ORANGE_CMR',
                country: 'CMR',
                currency: 'XAF',
                prefix: '237',
                rate: 656,
                decimal: 0,
            },
        ],
    },
    {
        name: 'benin',
        flag: benin,
        rate: 656,
        sim: [
            {
                mno: 'MTN',
                correspondent: 'MTN_MOMO_BEN',
                country: 'BEN',
                currency: 'XOF',
                prefix: '229',
                rate: 656,
                decimal: 0,
            }
        ],
    },
]