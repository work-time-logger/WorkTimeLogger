using System;
using RestSharp;
using MsgPack.Serialization;
using WorkTimeLoggerEmulator.ApiClient.DTO;

namespace WorkTimeLoggerEmulator.ApiClient
{
    public class Client
    {
        private readonly string _token;
        private readonly RestClient _client;

        public Client(string endpoint, string token)
        {
            _token = token;
            _client = new RestClient(endpoint);
        }

        public CardQueryResponse Query(string card_id)
        {
            var request = new RestRequest("card/{card_id}", Method.GET);
            request.AddUrlSegment("card_id", card_id);
            request.AddHeader("Accept", "application/msgpack");
            request.AddHeader("Authorization", $"Bearer {_token}");
            
            var response = _client.Execute(request);

            if ((int) response.StatusCode != 200)
            {
                throw new Exception($"NonSuccesfull: {response.StatusCode}");
            }

            var serializer = MessagePackSerializer.Get<CardQueryResponse>();
            return serializer.UnpackSingleObject(response.RawBytes);
        }

        public WorkStartedResponse Start(string card_id)
        {
            var request = new RestRequest("card/{card_id}/start", Method.POST);
            request.AddUrlSegment("card_id", card_id);
            request.AddHeader("Accept", "application/msgpack");
            request.AddHeader("Authorization", $"Bearer {_token}");
            
            var response = _client.Execute(request);

            if ((int) response.StatusCode != 200)
            {
                throw new Exception($"NonSuccesfull: {response.StatusCode}");
            }
            
            var serializer = MessagePackSerializer.Get<WorkStartedResponse>();
            return serializer.UnpackSingleObject(response.RawBytes);
        }

        public WorkStoppedResponse Stop(string card_id, string entry_uuid)
        {
            var request = new RestRequest("card/{card_id}/stop/{entry_uuid}", Method.POST);
            request.AddUrlSegment("card_id", card_id);
            request.AddUrlSegment("entry_uuid", entry_uuid);
            request.AddHeader("Accept", "application/msgpack");
            request.AddHeader("Authorization", $"Bearer {_token}");
            
            var response = _client.Execute(request);

            if ((int) response.StatusCode != 200)
            {
                throw new Exception($"NonSuccesfull: {response.StatusCode}");
            }
            
            var serializer = MessagePackSerializer.Get<WorkStoppedResponse>();
            return serializer.UnpackSingleObject(response.RawBytes);
        }
    }
}