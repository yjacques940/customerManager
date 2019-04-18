using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.DTO
{
    public class ResponseFromWebInformation
    {
        public int IdQuestion { get; set; }
        public bool? ResponseBool { get; set; }
        public string ResponseString { get; set; } = "";
        public string AnswerType { get; set; }
    }
}
